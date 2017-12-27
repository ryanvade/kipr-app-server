import 'rxjs/add/operator/map';
import { Http } from '@angular/http';
import 'rxjs/add/operator/toPromise';
import { Injectable } from '@angular/core';
import { SettingsProvider } from '../settings/settings';

/*
  Generated class for the CompetitionProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class CompetitionProvider {

  constructor(public http: Http, public settings: SettingsProvider) {

  }

  async getCompetitions() {
    let serverName = await this.settings.getServerName();
    return this.http.get(serverName + "/api/competitions")
      .map(res => res.json())
      .toPromise();
  }

}
