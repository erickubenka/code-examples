import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {RouterModule} from "@angular/router";

import {AppComponent} from './app.component';
import {AdminComponent} from './admin/admin.component';
import {DisplayComponent} from './display/display.component';

@NgModule({
  declarations: [
    AppComponent,
    AdminComponent,
    DisplayComponent
  ],
  imports: [
    BrowserModule,
    RouterModule.forRoot([
      {path: 'admin', component: AdminComponent},
      {path: 'display', component: DisplayComponent}
    ]),
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
}
