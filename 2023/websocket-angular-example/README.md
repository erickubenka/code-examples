### Die Idee
Für einen Geburtstag im Bekanntenkreis wollte ich ein kleines Spiel einer Gameshow aus dem Fernsehen als Client-Server-Anwendung nachbauen. Dabei soll sich ein Administrator als jener ausgeben können und als Spielleiter fungieren, während sich Clients zur reinen Anzeige der Daten, beziehungsweise des Spiels, zum Server verbinden und in Echtzeit die aktuellen Fragen, Timer, Antwortversuche und Lösungen anzeigen, sobald der Spielleiter diese zur Anzeige freigibt. 

### Angular Boiler Plate
Für den Start mit einem Angular Template, nutzte ich die [angular-docker-boilerplate](https://github.com/erickubenka/angular-docker-boilerplate), welche ich mir zu diesem Zweck vor eingier Zeit geschrieben habe.

### WebSocket Server
Um die Hürde der Echtzeitkommunikation zu meistern, entschied ich mich für einen WebSocket-Server, welcher die Daten des Spielleiters empfängt und an alle Clients/Displays broadcasten soll.
Dazu legte ich mir ein `server` Verzeichnis in meinem Projekt-Root an und platzierte eine simple `package.json`-File mit folgenden Abhängikeiten darion:

#### server/package.json
````json
{
  "dependencies": {
    "ws": "^8.11.0"
  }
}
````

Die Abhängikeiten können dann mittels `npm install` entsprechend installiert werden. Der Server-Code für mein Broadcasting Anliegen sieht dann wie foglt aus und wurde in `server.mjs` abgelegt. Dieser einfache Code wird bei Ausführung einen WebSocket-Server auf Port 8080 starten und sobald er Daten empfängt, diese an alle registrierten Clients außer sich selbst senden. Das ist nun nicht der sicherste Ansatz, da jeder Client Daten senden kann ohne das eine serverseitige Validierung/Authentifiaktion stattfindet, aber für meinen Zweck vollkommen ausreichend. 

#### server/server.mjs
````typescript
import {WebSocketServer} from 'ws';

const wss = new WebSocketServer({port: 8080});

// websocket for real time display
wss.on('connection', function connection(ws) {
    console.log("Somebody connected to me.")

    ws.on('message', function message(data, isBinary) {
        console.log(`Received message, broadcasting to ${wss.clients.size - 1} clients.`);
        wss.clients.forEach(function each(client) {
            if (client !== ws && client.readyState === 1) {
                client.send(data, {binary: isBinary});
            }
        });
    });
});
````

### WebSocket in Angular einbinden
Nun galt es den Server entsprechend im Angular einzubinden. Dazu erstellte ich mir einen entsprechenden Service, welcher dann in den Admin und Display Modulen verwendet werden kann. Außerdem brauche ich zwei Komponenten, jeweils eine für die Admin- und Client-Seite
````
ng generate service services/web-socket
ng generate component admin
ng generate comopnent display
````

Der `service/web-socket.service.ts` beinhaltet neben einer kleinen `struct`-Definitoon für die Nachrichten im Wesentlichen zwei Funktionen. Zum einen wird bei Instaziierung und dem damit verbundenem Konstruktoraufruf die Verbindung zum WebSocket-Server auf Port 8080 hergestellt. Soabld diese Verbindung hergestellt wurde, wird eine Liste mit `Message`-Objekten entsprechend gefüllt, sobald diese vom WebSocket-Server empfangen werden.
Die zweite Funktion ist `sendMessage` welche entsprechend vom Admin-Interface aus aufgerufen werden muss, wenn die Daten aktualisiert werden sollen.

#### service/web-socket.service.ts
````typescript
import {Injectable} from '@angular/core';

import {Observable, Observer} from 'rxjs';
import {AnonymousSubject} from 'rxjs/internal/Subject';
import {Subject} from 'rxjs';
import {map} from 'rxjs/operators';

const CHAT_URL = "ws://localhost:8080";

export interface Message {
  key: string;
  data: any;
}

@Injectable({
  providedIn: 'root'
})
export class WebSocketService {

  private subject: AnonymousSubject<MessageEvent>;
  public messages: Subject<Message>;

  constructor() {
    this.subject = this.connect(CHAT_URL);
    this.messages = <Subject<Message>>this.subject.pipe(
      map(
        (response: MessageEvent): Message => {
          console.log(response.data);
          let data = JSON.parse(response.data);
          return data;
        }
      )
    );
  }

  public sendMessage(key: string, data: any) {
    let message = {
      key: key,
      data: data
    };
    this.messages.next(message);
  }

  public connect(url: any): AnonymousSubject<MessageEvent> {
    if (!this.subject) {
      this.subject = this.create(url);
      console.log("Successfully connected: " + url);
    }
    return this.subject;
  }

  private create(url: any): AnonymousSubject<MessageEvent> {
    let ws = new WebSocket(url);
    let observable = new Observable((obs: Observer<MessageEvent>) => {
      ws.onmessage = obs.next.bind(obs);
      ws.onerror = obs.error.bind(obs);
      ws.onclose = obs.complete.bind(obs);
      return ws.close.bind(ws);
    });

    let observer = {
      error: (err: any) => {
        console.log(err);
      },
      complete: () => {
        console.log("");
      },
      next: (data: Object) => {
        waitForSocketConnection(ws, function () {
          if (ws.readyState === WebSocket.OPEN) {
            console.log('Message sent to websocket: ', data);
            ws.send(JSON.stringify(data));
          }
        })
      }
    };
    return new AnonymousSubject<MessageEvent>(observer, observable);
  }%MCEPASTEBIN%
}

function waitForSocketConnection(socket: any, callback: any) {
  setTimeout(
    function () {
      if (socket.readyState === 1) {
        console.log("Connection is made")
        if (callback != null) {
          callback();
        }
      } else {
        console.log("wait for connection...")
        waitForSocketConnection(socket, callback);
      }
    }, 50); // wait 50 milisecond for the connection...
}
````

Jeweils in `display.component.ts`, als auch in `admin.component.ts` muss der `WebSocketService` im Konstruktor als Abhängigkeit instaziiert werden.
Auf der Client-Seite werden die Daten der `webSocketService.messages`-List einfach mittels einem `subscribe()` abgerufen und der Komponentenvariable `displayData` zugewiesen. Somit kann die UI direkt auf neue Daten des WebSocket-Servers reagieren.

#### display.component.ts
````
export class DisplayComponent {

  public displayData: Message = {
    key: "demo",
    data: "No data received yet."
  };

  constructor(private webSocketService: WebSocketService) {
    webSocketService.messages.subscribe(value => {
      this.displayData = value;
    });
  }
}
````

Im `admin.component.ts` wird entsprechend bei Initialisierung direkt ein Aufruf `webSocketService.sendMessage()` durchgeführt um den Status `started` zu übermitteln. Für Demo-Zwecke wird in der Admin-UI ein Button implementiert, welcher bei Jedem Klick den Counter erhöhren soll und via WebSocket an den Client übertragen soll. 

#### admin.component.ts
````
export class AdminComponent {

  counter: number = 0;

  constructor(private webSocketService: WebSocketService) {
    this.webSocketService.sendMessage("demo", {"state": "started", "counter": this.counter});
  }

  updateCounter() {
    this.counter++;
    this.webSocketService.sendMessage("demo", {"state": "started", "counter": this.counter});
  }
}
````

Die beiden Komponenten habe ich für Demozwecke dann auf den Routen `/admin` und `/display` im Root-Module der Angular-Anwendung deklariert und die Anzeige entsprechend vorgenommen. Wenn der WebSocket-Server  gestratet ist, wird auf der `display`-Route sobald die `admin`-Route aufgerufen wurde, entsprechend der Status auf `started` gesetzt und mit jedem Klick auf die Schaltfläche der Admin-Seite in Echtzeit der Wert für `displayData.data.counter` um 1 erhöht.

Das vollständige Code-Beispiel ist wie üglich in meinem [GitHub-Repo](https://github.com/erickubenka/code-examples/2023/websocket-angular-example) abrufbar.
