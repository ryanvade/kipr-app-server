import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { JudgingPage } from '../judging/judging';
import { MatchProvider } from '../../providers/match/match';

/**
 * Generated class for the MatchesPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-matches',
  templateUrl: 'matches.html',
})
export class MatchesPage {

  matches: Object[] = [];
  page: number = 1;
  maxMatches: number = 0;
  maxPages: number = 0;
  constructor(public navCtrl: NavController, public navParams: NavParams, public matchProvider: MatchProvider) {
    this.getMatches();
  }

  teamTapped(name,match){
    this.navCtrl.push(JudgingPage,
      {
        name: name,
        match: match,
        judgedOpponent: false
      }
    );
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad MatchesPage');
  }

  async getMatches() {
    console.log(this, this.matches);
    let response = await this.matchProvider.getMatches();
    this.matches = this.matches.concat(response.json().data);
    this.maxMatches = response.json().total;
    this.maxPages = response.json().last_page;
  }

}
