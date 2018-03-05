import { Injectable } from '@angular/core';
import { Socket } from 'ng-socket-io';
import { Http } from '@angular/http';
import { SettingsProvider } from '../settings/settings';
import 'rxjs/add/operator/map';

/*
  Generated class for the SocketProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class SocketProvider extends Socket{

  constructor() {
    let url = "https://kipr-app.dev:3000";
    super({ url: url, options: {secure:true}});
  }

}
