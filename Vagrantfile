Vagrant::Config.run do |config|
  config.vm.box       = "zend-server"
  config.vm.box_url   = "../../CMN/boxes/zend-server/package.box"
  config.vm.host_name = "ericclemmons.github.com"

  # Dedicated IP to avoid conflicts (and no port fowarding!)
  config.vm.network :hostonly, "33.33.33.2"

  # Remount the default shared folder as NFS for caching & speed
  config.vm.share_folder("v-root", "/vagrant", ".", :nfs => true)

  # Setup the Site
  config.vm.provision :chef_solo do |chef|
    chef.add_recipe("assetic")
  end
end
