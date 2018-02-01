import { Component } from '@angular/core';
import { IonicPage,NavController, NavParams } from 'ionic-angular';
import { AlertController } from 'ionic-angular';
import { TeamProvider } from '../../providers/team/team';
import { SettingsProvider } from '../../providers/settings/settings';


@IonicPage()
@Component({
  selector: 'page-signIn',
  templateUrl: 'signIn.html'
})

export class SignInPage {

teams: string[];
teamName: string;
competitionID: number;
private displayNoResults: Boolean;
private loading: Boolean = true;

constructor(public navCtrl: NavController, public navParams:NavParams, private alertCtrl: AlertController, private TeamPrvdr: TeamProvider, private settingsPrvdr: SettingsProvider){
  this.competitionID = settingsPrvdr.getCompetitionID();
  TeamPrvdr.getRegisteredTeamsInComp(this.competitionID).then(val => {
    this.teams = val;
    if (this.teams.length <= 0) {
      this.displayNoResults = true;
    }
    this.loading = false;
  }).catch(err => {console.error(err);});
}


teamSignedIn(name){
  let alert = this.alertCtrl.create({
    title: 'Confirmation',
    subTitle: 'You are signed in!',
    buttons: [
      {
        text: 'Ok',
        handler: (getTeamSignIn) => {
        console.log('Sign in confirmed');
        }
      },
      {
        text: 'Exit',
        handler: () => {
          console.log('Canceled')
        }
      }
    ]
  });
  alert.present();
}


ionViewDidLoad() {
    console.log('ionViewDidLoad SignInPage');
  }
}
