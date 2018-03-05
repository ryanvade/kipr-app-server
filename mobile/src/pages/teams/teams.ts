import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { TeamProvider } from '../../providers/team/team';

/**
 * Generated class for the TeamsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-teams',
  templateUrl: 'teams.html',
})
export class TeamsPage {

  teams: Object[] = [];
  search: String = "";
  page: number = 1;
  maxTeams: number = 1;
  maxPages: number = 1;
  constructor(public navParams: NavParams, public teamProvider: TeamProvider) {
    this.getTeams();
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad TeamsPage');
  }

  doInfinite(event) {
    setTimeout(() => {
      if(this.page < this.maxPages) {
        this.page++;
        this.getTeams();
      }else {
        event.enable(false);
      }
      event.complete();
    }, 500);
  }

  searchForTeams(event) {
    this.search = event.target.value;
    this.page = 1;
    this.teams = [];
    let is = document.getElementsByTagName("ion-infinite-scroll-content")[0];
    is.setAttribute('state', "enabled");
    this.getTeams();
  }

  async getTeams() {
    let response = await this.teamProvider.getAllTeams(this.search, this.page);
    this.teams = this.teams.concat(response.json().data);
    this.maxTeams = response.json().total;
    this.maxPages = response.json().last_page;
  }

}
