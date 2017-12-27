import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import { Storage } from '@ionic/storage';
import 'rxjs/add/operator/map';

/*
  Generated class for the SettingsProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class SettingsProvider {

  constructor(public http: Http, public storage: Storage) {
    console.log("Starting Settings Provider");
  }

  async getServerName() {
    return await this.storage.get('settings:server_name');
  }

  async getFirstTimeUse() {
    return await this.storage.get('settings:first_time_use');
  }

  setDefaults() {
    console.log("Setting Default Values");
    // TODO: Defaults Object
    this.setServerName('https://kipr.ryanowens.info');
    this.setFirstTimeUse('TRUE');
  }

  setFirstTimeUse(val) {
    this.storage.set('settings:first_time_use', val);
  }

  setServerName(name) {
    this.storage.set('settings:server_name', name);
  }

}
