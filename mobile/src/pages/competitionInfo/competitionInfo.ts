import { Component } from '@angular/core';
import { IonicPage,NavController, NavParams } from 'ionic-angular';
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
competitionID: number;
private displayNoResults: Boolean;
private loading: Boolean = true;

constructor(public navCtrl: NavController, public navParams:NavParams, private alertCtrl: AlertController, 
    private TeamPrvdr: TeamProvider, private settingsPrvdr: SettingsProvider, private compPrvdr: CompetitionProvider){
      this.getTeamList();
    }

async getTeamList(){
  this.teamName = await this.compPrvdr.getRegisteredTeamsInComp(compID);
  this.TeamPrvdr.getRegisteredTeamsInComp(this.competitionID).then(val => {
   teamName = val;
    if (this.teams.length <= 0) {
      this.displayNoResults = true;
    }
    this.loading = false;
    }).catch(err => {console.error(err);});
}}