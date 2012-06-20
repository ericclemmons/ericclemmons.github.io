Vagrant::Config.run do |config|
  # Vagrant box config (Ubuntu 10.04)
  config.vm.box       = "lucid32"
  config.vm.box_url   = "http://files.vagrantup.com/lucid32.box"
  config.vm.host_name = "ericclemmons.github.com"
  config.vm.forward_port 80, 8080, :auto => true

  # Mount local SSH keys (required for publishing)
  config.vm.share_folder('ssh', "/home/vagrant/.ssh", File.expand_path("~/.ssh"))

  # Copy over client's .gitconfig (required for publishing)
  gitconfig = `cat ~/.gitconfig`
  config.vm.provision :shell do |shell|
    shell.inline = "cat > /home/vagrant/.gitconfig << CONFIG\n#{gitconfig}\nCONFIG\n"
  end

  # Setup the VM
  config.vm.provision :chef_solo do |chef|
    chef.cookbooks_path = "vendor/cmn/cookbooks"

    chef.add_recipe("base")
    chef.add_recipe("zend")
    chef.add_recipe("java")
    chef.add_recipe("assetic")
  end
end
