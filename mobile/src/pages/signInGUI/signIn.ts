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
  this.teams = ['Team A','Team B','Team C','Team D','Team E','Team F'];
}

ionViewDidLoad() {
    console.log('ionViewDidLoad SignInPage');
  }
}
