# LUKS Server-LVM beim Boot mittels Dropbear via Wifi entsperren

Ich habe mir letztens einen kleinen Tiny PC gekauft, welcher als Server für kleine Docker-Container dienen soll. Home
Assistant, ein kleines Grafana und ein bisschen Logging. Der Standarkram für den Heimautomatisierungsbedarf eben.
Der kleine PC hat Wifi und soll in einer Ecke stehen, welche nicht über ein LAN Kabel erreichbar ist.
Aufgesetzt hab ich den Kleinen mit einem schlanken Debian 13 System und LVM mit LUKS Verschlüsselung für die Platten.
Der aktuelle Standard eben. - Bis mir dann aufgefallen ist, dass ich Platten ja entschlüsseln muss, wenn
ich ihn mal neu starte oder ein Stromverlust zum Reboot führt.
Also schnell ein Dropbear SSH Server im `initramfs` aufgesetzt und den Wifi Netzwerkadapter ebenfalls noch im `initramfs`
verfügbar gemacht, damit ich das Decrpyt-Passwort auch per SSH über Wifi eingeben kann.

## Wifi Kernelmodule im initramfs verfügbar machen

In meinem Server ist Intel Wifi Karten verbaut. Um die entsprechenden Kernelmodule `iwlwifi` und `iwlmvm`  im `initramfs`
verfügbar zu machen, müssen diese in der Datei `/etc/initramfs-tools/modules` eingetragen werden:

```
# List of modules that you want to include in your initramfs.
# They will be loaded at boot time in the order below.
#
# Syntax:  module_name [args ...]
#
# You must run update-initramfs(8) to effect this change.
#
# Examples:
#
# raid1
# sd_mod

iwlwifi
iwlmvm
````

## Dropbear installieren und im initramfs verfügbar machen

Dropbear, ist wie bereits erwähnt, ein kleiner und äußerst ressourcenfreundlicher SSh Server, welcher im `initramfs` kann,
also dem kleinen Dateisystem, welches beim Booten geladen wird, bevor das eigentliche System gestartet wird.
Um Dropbear zu installieren, folgte ich einfach der entsprechenden Anleitung
im [Debian Wiki](https://wiki.debian.org/DropBear).

In der `/etc/initramfs-tools/initramfs.conf` Datei muss das WLAN Interface im `DEVICE` Block angegeben werden. Außerdem
eine IP-Config, in meinem Fall einfach, da ich die IP via DHCP beziehe.

````
#
# DEVICE: ...
#
# Specify a specific network interface, like eth0
# Overridden by optional ip= or BOOTIF= bootarg
#

DEVICE=wlp2s0
IP=:::::wlp2s0:dhcp
````

Anschließend muss man unbedingt darauf achten, dass die Host-Keys auch im Dropbear Verzeichnis liegen und vorher in ein
Dropbear-fähiges Format konvertiert werden, damit man beim SSH zum Dropbear SSH Server und dem eigentlichen SSH Server
nach dem Boot nicht jedes mal in eine Host Key Verification Warning läuft.

````
dropbearconvert openssh dropbear /etc/ssh/ssh_host_ecdsa_key /etc/dropbear/initramfs/dropbear_ecdsa_host_key
dropbearconvert openssh dropbear /etc/ssh/ssh_host_rsa_key /etc/dropbear/initramfs/dropbear_rsa_host_key
dropbearconvert openssh dropbear /etc/ssh/ssh_host_ed25519_key /etc/dropbear/initramfs/dropbear_ed25519_host_key
````

Das Update des initramfs nicht vergessen!

## WLAN-Daten mittels WPA Supplicant konfigurieren

Das Gröbste ist nun bereit geschafft, doch fehlt noch die Konfiguration des WLANs. Also SSID und Passwort.

Hier habe ich mir eine WPA Supplicant Konfigurationsdatei erstellt. Dazu habe ich
das [wifi-on-debian-initramfs](https://github.com/fangfufu/wifi-on-debian-initramfs)-Repository verwendet, um die
Dateien dann auch entsprechend im initramsfs verfügbar zu machen.

Für WPA2 ist das `proto` immer RSN, für die Werte von `pairwise` und `group` habe zuvor mittels `iw dev wlp2s0 scan`
ermittelt.

````
# sample /etc/initramfs-tools/wpa_supplicant.conf
# note that this is independent of the system /etc/wpa_supplicant.conf (if any)
# only add the network you need at boot time. **And keep the ctrl_interface** !!
ctrl_interface=/tmp/wpa_supplicant

network={
    ssid="WLAN-SSID"
    scan_ssid=1
    proto=RSN
    psk="PASSWORT"
    key_mgmt=WPA-PSK
    pairwise=CCMP
    group=CCMP
}
````

Thats it. Wenn man den Anweisungen gefolgt ist, sollte nun beim nächsten Reboot ein Dropbear SSH Server im `initramfs`
laufen, welcher über das Wifi Interface erreichbar ist.

### Links

- [Debian Wiki :: Dropbear](https://wiki.debian.org/DropBear)
- [GitHub :: wifi-on-debian-initramfs](https://github.com/fangfufu/wifi-on-debian-initramfs)
- [Ubuntu Wiki :: WPA Supplicant](https://wiki.ubuntuusers.de/WLAN/wpa_supplicant/)