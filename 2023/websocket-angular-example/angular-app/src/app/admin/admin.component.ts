import {Component} from '@angular/core';
import {WebSocketService} from "../services/web-socket.service";

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.css']
})
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
