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

}
