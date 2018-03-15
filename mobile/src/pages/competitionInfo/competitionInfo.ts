import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { AlertController } from 'ionic-angular';
import { TeamProvider } from '../../providers/team/team';
import { SettingsProvider } from '../../providers/settings/settings';
import { CompetitionProvider } from '../../providers/competition/competition';

@IonicPage()
@Component({
  selector: 'page-competitionInfo',
  templateUrl: 'competitionInfo.html'
})

export class CompetitionInfoPage {

  teams: Object[];
  teamName: string;
  page: number;
  maxPages: number;
  competition: {
    created_at: Date,
    end_date: Date,
    id: number,
    location: String,
    name: String,
    ruleset_id: number,
    start_date: Date,
    updated_at: Date
  };
  competitionID: number;
  teamTotal: number;
  private displayNoResults: Boolean;
  private loading: Boolean = true;

  constructor(public navCtrl: NavController, public navParams: NavParams, private alertCtrl: AlertController,
    private TeamPrvdr: TeamProvider, private settingsPrvdr: SettingsProvider, private compPrvdr: CompetitionProvider) {
    // console.log(navParams);
    this.competition = navParams.get('competition');
    this.competitionID = this.competition.id;
    this.page = 1;
    this.teams = [];
    this.getTeamList();
    this.teamTotal = 0;
  }

  async getTeamList() {
    this.teamName = await this.compPrvdr.getRegisteredTeamsInComp(this.competitionID, this.page);
    this.compPrvdr.getRegisteredTeamsInComp(this.competitionID).then(val => {
      this.teams = this.teams.concat(val.data);
      this.maxPages = val.last_page;
      this.teamTotal = val.total;
      if (this.teams.length <= 0) {
        this.displayNoResults = true;
      }
      this.loading = false;
    }).catch(err => { console.error(err); });
  }

  doInfinite(event) {
    setTimeout(() => {
      if(this.page < this.maxPages) {
        this.page++;
        this.getTeamList();
      }else {
        event.enable(false);
      }
      event.complete();
    }, 500);

  }
}
