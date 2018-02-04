import { Injectable } from '@angular/core';
import { Http, Headers } from '@angular/http';
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
//sign team into competition with their assigned teamID and compID
  async getTeamSignIn(teamID, compID) {
    let serverName = await this.settings.getServerName();
    if (serverName == null) {
      serverName = 'https://kipr.ryanowens.info'; // TODO: set default value
    }
    let headers = new Headers();
    headers.append('Authorization', 'Bearer a3dfd282b21de6549f195f901a585b226941a38debe24e4a3b9aeded932165edc03e65d097c1f0bc');
    return this.http.post(serverName + "/api/competition/" + compID + " /team/" + teamID + "/signin", '', {headers : headers})
      .map(res => res.json().data)
      .toPromise();
  }
//list all teams in a competition
  async getRegisteredTeamsInComp(compID){
    let serverName = await this.settings.getServerName();
    if (serverName == null) {
      serverName = 'https://kipr.ryanowens.info'; // TODO: set default value
    }
    return this.http.get(serverName + "/api/competition/" + compID + "/team?signed_in=0")
    .map(res => res.json())
    .toPromise();
  }
}
