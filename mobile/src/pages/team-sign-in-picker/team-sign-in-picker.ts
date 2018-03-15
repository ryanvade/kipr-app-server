import { Component } from '@angular/core';
import { AlertController } from 'ionic-angular';
import { TeamProvider } from '../../providers/team/team';
import { IonicPage, NavController, NavParams, ViewController } from 'ionic-angular';

/**
 * Generated class for the TeamSignInPickerPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-team-sign-in-picker',
  templateUrl: 'team-sign-in-picker.html',
})
export class TeamSignInPickerPage {

  competitionID: number;
  teams: Object[];
  group: Object;
  constructor(public params: NavParams, private TeamPrvdr: TeamProvider, private alertCtrl: AlertController, public viewCtrl: ViewController) {
    this.teams = params.get('teams');
    this.group = params.get('group');
    this.competitionID = params.get('compID');
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad TeamSignInPickerPage');
  }

  //sign team in to be added to competition bracket
  teamSignIn(team) {
    this.TeamPrvdr.getTeamSignIn(team.id, this.competitionID).then(() => {
      let alert = this.alertCtrl.create({
        title: 'Confirmation',
        subTitle: `Team ${team.code} is signed in!`,
        buttons: [
          {
            text: 'Ok',
            handler: (getTeamSignIn) => {
              this.teams.splice(this.teams.indexOf(team), 1);
              this.group[1].splice(this.group[1].indexOf(team), 1);
              console.log('Sign in confirmed');
              if(this.group[1].length <= 0) {
                this.viewCtrl.dismiss();
              }
            }
          },
          {
            text: 'Cancel',
            handler: () => {
              this.cancelSignIn(team);
              console.log('Canceled');
                this.viewCtrl.dismiss();
            }
          }
        ]
      });
      alert.present();

    }).catch((error) => { console.error(error); });
  }

  async cancelSignIn(team) {
    await this.TeamPrvdr.unRegisterATeam(team.id, this.competitionID);
  }

}
