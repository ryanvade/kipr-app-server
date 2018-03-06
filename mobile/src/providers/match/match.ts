import 'rxjs/add/operator/map';
import { Http } from '@angular/http';
import 'rxjs/add/operator/toPromise';
import { Injectable } from '@angular/core';
import { SettingsProvider } from '../settings/settings';

@Injectable()
export class MatchProvider {

  constructor(public http: Http, public settings: SettingsProvider) {

  }

    async getMatches(page = 1) {
      let serverName = await this.settings.getServerName();
        if(serverName == null) {
            serverName = 'https://kipr.ryanowens.info';
        }
        let url = serverName + "/api/match?page=" + page;
        return await this.http.get(url).toPromise();
    }
}
