import { Component } from '@angular/core';
import { IonicPage,NavController, NavParams } from 'ionic-angular';
import { AlertController } from 'ionic-angular';
import { TeamProvider } from '../../providers/team/team';
import { SettingsProvider } from '../../providers/settings/settings';
import { ModalController } from 'ionic-angular';
import { TeamSignInPickerPage } from '../team-sign-in-picker/team-sign-in-picker';


@IonicPage()
@Component({
  selector: 'page-signIn',
  templateUrl: 'signIn.html'
})

export class SignInPage {

teams: Object[];
teamName: string;
competitionID: number;
private displayNoResults: Boolean;
private loading: Boolean = true;

//get list of teams in competition
constructor(public navCtrl: NavController, public navParams:NavParams,
  private TeamPrvdr: TeamProvider, private settingsPrvdr: SettingsProvider, public modalCtrl: ModalController){
    this.getTeamsSignedin();
}

async getTeamsSignedin()
{
  this.competitionID = await this.settingsPrvdr.getSignInCompetitionID();
  this.competitionID = 1; // TODO: Change back
  this.TeamPrvdr.getRegisteredTeamsInComp(this.competitionID).then(val => {
    const map = new Map();
    val.forEach((team) => {
      const key = team.name;
      const collection = map.get(key);
      if(!collection){
        map.set(key, [team]);
      }
    else {
        collection.push(team);
      }
    });

    this.teams = Array.from(map);
    console.log(this.teams);
    if (this.teams.length <= 0) {
      this.displayNoResults = true;
    }
    this.loading = false;
  }).catch(err => {console.error(err);});
}

displayTeamChooser(group) {
  let modal = this.modalCtrl.create(TeamSignInPickerPage, {
    group: group,
    teams: this.teams,
    compID: this.competitionID
  });
  modal.present();
}




ionViewDidLoad() {
    console.log('ionViewDidLoad SignInPage');
  }
}
