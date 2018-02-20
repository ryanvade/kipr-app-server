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
<<<<<<< HEAD
    let token = await this.settings.getSignInAuthToken();
    headers.append('Authorization', 'Bearer ' + token);
=======
    headers.append('Authorization', 'Bearer a3dfd282b21de6549f195f901a585b226941a38debe24e4a3b9aeded932165edc03e65d097c1f0bc');
>>>>>>> 4df4213e9ebb2f4b8add0e01e6eb33d8c7d27f13
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
<<<<<<< HEAD
    return this.http.get(serverName + "/api/competition/" + compID + "/team?signed_in=0")
    .map(res => res.json())
    .toPromise();
  }

  async unRegisterATeam(teamID, compID) {
    let serverName = await this.settings.getServerName();
    if (serverName == null) {
      serverName = 'https://kipr.ryanowens.info'; // TODO: set default value
    }
    let headers = new Headers();
    let token = await this.settings.getSignInAuthToken();
    headers.append('Authorization', 'Bearer ' + token);
    let url = serverName + "/api/competition/" + compID + "/team/" + teamID + "/signin";
    return await this.http.delete(url,  {headers : headers})
      .map(res => res.json().data)
      .toPromise();
  }
=======
    return this.http.get(serverName + "/api/competition/" + compID + "/team")
    .map(res => res.json())
    .toPromise();
  }
>>>>>>> 4df4213e9ebb2f4b8add0e01e6eb33d8c7d27f13
}
