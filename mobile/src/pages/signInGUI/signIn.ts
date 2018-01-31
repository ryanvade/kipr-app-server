import { Component } from '@angular/core';
import { IonicPage,NavController, NavParams } from 'ionic-angular';
import { AlertController } from 'ionic-angular';
import { TeamProvider } from '../../providers/team/team';


@IonicPage()
@Component({
  selector: 'page-signIn',
  templateUrl: 'signIn.html'
})

export class SignInPage {

teams: string[];
teamName: string;

constructor(public navCtrl: NavController, public navParams:NavParams, private alertCtrl: AlertController, private TeamPrvdr: TeamProvider){
  this.teams = ['Team Name 1','Team Name 2','Team Name 3','Team Name 4','Team Name 5','Team Name 6'];
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
