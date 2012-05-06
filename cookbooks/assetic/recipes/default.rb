include_recipe "nodejs"
include_recipe "nodejs::npm"

# Assetic Dependencies
gem_package "compass"

["less", "stylus"].each do |package|
    npm_package "#{package}" do
        action :install_local
    end
end
