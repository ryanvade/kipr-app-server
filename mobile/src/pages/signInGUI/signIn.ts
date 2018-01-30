import { Component } from '@angular/core';
import { IonicPage,NavController, NavParams } from 'ionic-angular';

@IonicPage()
@Component({
  selector: 'page-signIn',
  templateUrl: 'signIn.html'
})

export class SignInPage {

teams: string[];

constructor(public navCtrl: NavController, public navParams:NavParams){
  this.teams = ['Team Name 1','Team Name 2','Team Name 3','Team Name 4','Team Name 5','Team Name 6'];
}

ionViewDidLoad() {
    console.log('ionViewDidLoad SignInPage');
  }
}
