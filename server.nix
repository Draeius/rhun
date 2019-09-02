{
  network.description = "rhun";

  rhun-dev =
    { config, pkgs, ... }:
    {
      environment.systemPackages = [ pkgs.php73 ];
      services.mysql = {
        enable = true;
        bind = "127.0.0.1";
        package = pkgs.mysql;
        ensureUsers = [
          {
            name = "rhun";
            ensurePermissions = {
              "rhun.*" = "ALL PRIVILEGES";
            };
          }
        ];
      };
      services.httpd = {
        enable = true;
        adminAddr = "foo@example.org";
        enablePHP = true;
        phpPackage = pkgs.php73;
        virtualHosts = [ {
          documentRoot = "/var/www/rhun/public";
#         serverAliases = "rhun";
          extraConfig =
          ''
          <Directory /var/www/rhun/public>
            AllowOverride All
            Order Allow,Deny
            Allow from All
          </Directory>
           '';
        } ];
      };
      networking.firewall.allowedTCPPorts = [ 80 ];
      deployment.targetEnv = "libvirtd";
    };
}
