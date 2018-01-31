import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/add/operator/map';
import { SettingsProvider } from '../settings/settings';

/*
  Generated class for the TeamProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class TeamProvider {

  constructor(public http: Http, public settings: SettingsProvider) {
    console.log('Hello TeamProvider Provider');
  }

  async getTeamSignIn(teamID, compID) {
    let serverName = await this.settings.getServerName();
    if (serverName == null) {
      serverName = 'https://kipr.ryanowens.info'; // TODO: set default value
    }
    return this.http.get(serverName + "/api/competition/" + compID + " /team/" + teamID + "/signin")
      .map(res => res.json().data)
      .toPromise();
  }

}
