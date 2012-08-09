# Unit-Testing Tips For Symfony2 & PHPUnit

## Optimize `AppKernel` In The `test` Environment

When in `debug` mode, every time the `AppKernel` is booted, it checks all tracked resources (e.g. templates, configuration, annotations, etc.) for modifications and recompiles the container as needed.

Since this only needs to occur *once* at the start of your test-suite, copy/paste the following code into your `AppKernel.php` file:

<https://gist.github.com/f4a420f6eb42579da883>

## Avoid Functional Tests, Favor Shallow Unit-Tests

I've had a bad habit of relying on functional tests.  These are especially slow when you use something like `DomCrawler` to crawl the rendered result of a controller.  Also, functional tests tend to be redundant because of their inherit overlap with other tests.

	class PostController extends Controller
	{
		public function viewAction($slug)
		{
			$post = $this->getPost($slug);

			if (!$post) {
				throw new \NotFoundHttpException('Could not find post by slug '.$slug);
			}

			return $this->render('BlogBundle:Post:view.html.twig', array(
				'post' => $post,
			));
		}

		public function getPost($slug)
		{
			$em  	= $this->getDoctrine()->getEntityManager();
			$repo	= $em->getRepository('BlogBundle:Post');

			return $repo->findBySlug($slug);
		}
	}

Previously, I tested this functionality by doing something *awful* like the following:

	public function testPostIsFoundAndRendersCorrectly()
	{
			$client   		= static::createClient();
			$container		= $client->getKernel()->getContainer();
			$em       					= $container->get('doctrine')->getEntityManager();
			$repo     				= $em->getRepository('BlogBundle:Post');
			$post     			= $repo->find(1);
			$url      		= sprintf('/posts/%s', $post->getSlug());
			$crawler  		= $client->request('GET', $url);
			$response 		= $client->getResponse();

			$this->assertEquals(200, $response()->getStatusCode());

			$title = (string) current($crawler->filter('h1'));
			$this->assertEquals($post->getTitle(), $title));
	}
		
There are several things wrongs about this:

* Entire application stack has to be booted.
* A database call is made.
* A pseudo-HTTP request is made to a hard-coded URL.
* The DOM has to be crawled just to verify the response.
* If there is a problem rendering, we still won't know what caused it.

Now, I test only test 1-level deep so we no longer have to rely on the internal workings of other methods:

	class PostControllerTest extends \PHPUnit_Framework_TestCase
	{
		/**
		 * @expectedException NotFoundHttpException
		 */
		public function testViewActionCallThrowsExceptionIfNotFound()
		{
				// Custom function to simplify mocking
				$controller = $this->mockController('PostController');
				$controller
						->expects($this->once())
						->method('getPost')
						->will($this->returnValue(null))
				;

				$controller->viewAction('dummy-slug');
		}

		public function testViewActionRendersPost()
		{
				$controller = $this->mockController('FormController');
				
				$post = new Post();
				$controller
						->expects($this->once())
						->method('getPost')
						->will($this->returnValue($post))
				;
				
				$controller
						->expects($this->once())
						->method('render')
						->with($this->equalTo(array('post' => $post)))
				;
		}
	}

The difference here is that the internals of `getPost` and `render` no longer matter.  Those will be tested separately as necessary.  Instead, the tests are confirming logic and mediating data between the request & the response.

## Use Mock Objects To Your Advantage

Mock objects have a built in "tally" system that allows you to  assert how many times a method is called.

This is *extremely* handy when you have logic that should break out of a loop, skip an expensive operation, or confirm that application flow never reached a method call.

	public function testSomeControllerAction()
	{
			// Only override specified methods
			$controller = $this->getMockBuilder('MyController')
					->setMethods(array('forward', 'render'))
					->getMock()
			;

			$controller->expects($this->never())->method('forward');
			$controller->expects($this->once())->method('render');

			$controller->viewAction('foo');
	}

In this example, we confirm that, given `foo`, `render` is called and `forward` is never called.

## Abstract Away & DRY Up Your Redundant Code

Writing tests that depend heavily on mock objects is a *great* way of seeing which method chains are used heavily and require repeated mocks.  When you see this, consider inserting a utility class or function to avoid mock-inception:

	$post = $this->getDoctrine()->getEntityManager()->getRepository('BlogBundle:Post')->findBySlug($slug);
	// vs.
	$post = $this->getRepository('BlogBundle:Post')->findBySlug($slug);
	// vs.
	$post = $this->getPost($slug);
