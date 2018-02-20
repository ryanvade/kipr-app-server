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

  async getAuthToken() {
    return await this.storage.get('auth:token');
  }

  async getSignInAuthToken(){
    return await this.storage.get('signInAuth:token');
  }

  async getSignInCompetitionID(){
    return await this.storage.get('signInAuth:competitionID');
  }
  //get the competitionID and return 1 for testing purposes


  setDefaults() {
    console.log("Setting Default Values");
    // TODO: Defaults Object
    this.setServerName('https://kipr.ryanowens.info');
    this.setFirstTimeUse('TRUE');
    this.setAuthToken('');
  }

  setFirstTimeUse(val) {
    this.storage.set('settings:first_time_use', val);
  }

  setServerName(name) {
    this.storage.set('settings:server_name', name);
  }

  setAuthToken(token) {
    this.storage.set('auth:token', token);
  }

  setSignInAuthToken(token){
    this.storage.set('signInAuth:token', token);
  }

  setSignInCompetitionID(id){
    this.storage.set('signInAuth:competitionID', id);
  }
}
