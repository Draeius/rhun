{ pkgs ? import <nixpkgs> {} }:

with pkgs;

let
  inherit (lib) optional optionals;
in

mkShell {
  buildInputs = [
    php73
    php73Packages.composer
    nodejs
    git
    nixops
  ];

  # Set up environment vars
  # We unset TERM b/c of https://github.com/NixOS/nix/issues/1056
  shellHook = ''
    export DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
    export TERM="xterm"
    export LANG="en_US.UTF-8"
    export LC_ALL="en_US.UTF-8"
  '';
}
