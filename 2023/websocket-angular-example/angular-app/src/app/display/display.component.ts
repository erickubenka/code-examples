import {Component} from '@angular/core';
import {Message, WebSocketService} from '../services/web-socket.service';

@Component({
  selector: 'app-display',
  templateUrl: './display.component.html',
  styleUrls: ['./display.component.css']
})
export class DisplayComponent {

  public displayData: Message = {
    key: "demo",
    data: {
      state: "stopped",
      counter: 0,
    }
  };

  constructor(private webSocketService: WebSocketService) {
    webSocketService.messages.subscribe(value => {
      this.displayData = value;
    });
  }
}
