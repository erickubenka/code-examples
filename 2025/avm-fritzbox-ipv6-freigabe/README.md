# IPv6 und DS-Lite Freigabe eines WebServers AVM Fritz!Box

Ich hab eine AVM Fritz!Box, bin an das Internet via Kabel-TV angeschlossen und habe einen Server im Heimnetzwerk, den
ich gerne im Internet verfügbar machen möchte. Ich habe durch meinen ISP nur einen DS-Lite Anschluss, was bedeutet, dass
ich
keine öffentliche IPv4 Adresse habe, sondern nur eine IPv6 Adresse. Um meinen Server trotzdem erreichbar zu machen, muss
ich also
IPv6 Freigaben in der Fritz!Box konfigurieren.

Ich zeig mal kurz, auf was bei mir zu achten war.

### IPv6 Adresse des Servers herausfinden

Zuerst muss man die Ipv6 Adresse des Servers, welcher freigeben werden soll, herausfinden. das geht entweder einfach
über die Fritz!Box Oberfläche oder über den Server selbst.

### IPv6 Freigabe in der Fritz!Box

Die Freigabe des Servers für die entsprechenden Ports via IPv6 erfolgt ganz einfach in der Fritz!Box Oberfläche unter
Freigaben.

Wichtig hier ist darauf zu achten, dass der Wert für das Feld `IPv6 Interface ID` 1:1 mit den letzten Stellen der IPv6
Adresse des Netzwerkinterfaces des freizugebenden Servers übereinstimmt. Ansonsten wird die Freigabe nicht
funktionieren.

Und das war es auch schon. Nun sollte der Server via IPv6 aus dem Internet erreichbar sein.

### Domain aufschalten

Bei Domainhoster eurer Wahl, bei mir in diesem Fall [INWX](https://inwx.com/), müsst ihr nun noch einen AAAA DNS Record
anlegen, welcher auf die IPv6 Adresse eures Servers zeigt. Wichtig an dieser Stelle. Auf die Adresse des Servers und
nicht der Fritz!Box.

### DNS-Rebindschutz deaktiveren

Wenn ihr nun versucht, eure Domain / euren Server von innerhalb eures Heimnetzwerkes zu erreichen, werdet ihr vermutlih
genauso enttäuscht werden, wie ich.  
Der Grund hierfür, der DNS-Rebindschutz der Fritz!Box. Dieser verhindert, dass Anfragen aus dem Heimnetzwerk an
Domains, welche auf die eigene IPv6 Adresse zeigen, beantwortet werden. Diesen Schutz müsste ihr für eure Domain in der
Fritz!Box Oberfläche ebenfalls noch deaktivieren.

Und nun sollte euer Server sowohl aus dem Internet, als auch aus dem Heimnetzwerk via Domain erreichbar sein.
