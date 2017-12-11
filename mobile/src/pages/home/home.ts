import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';
import { MatchesPage } from '../matches/matches';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {

  constructor(public navCtrl: NavController) {

  }

  matches(){
    this.navCtrl.push(MatchesPage,{})
  }

}
