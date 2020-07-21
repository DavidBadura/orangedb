# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = 'ubuntu/bionic64'

  config.vm.network 'private_network', ip: '192.168.222.242'
  config.vm.synced_folder "./", "/srv/share", id: 'vagrant-share', :nfs => true
  config.vm.synced_folder ".", "/vagrant", disabled: true
  config.ssh.forward_agent = true
  config.ssh.insert_key = false

  config.vm.provider "virtualbox" do |v|
    v.memory = 2048
    v.cpus = 2
    v.customize ["modifyvm", :id, "--ioapic", "on"]
    v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
  end

  config.vm.provision 'ansible_local' do |ansible|
    ansible.provisioning_path  = '/srv/share/ansible'
    ansible.playbook           = 'site.yml'
    ansible.inventory_path     = 'hosts'
    ansible.limit              = 'devbox'
    ansible.compatibility_mode = '2.0'
    ansible.extra_vars         = { ansible_python_interpreter: '/usr/bin/python3' }
  end
end
