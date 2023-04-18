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
  }
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
