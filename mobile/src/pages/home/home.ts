import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';
import { MatchesPage } from '../matches/matches';
import { SettingsProvider } from '../../providers/settings/settings';
import { CompetitionProvider } from '../../providers/competition/competition';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {

  private competitions: Array<Object>;
  private apiError: Boolean;
  private loadComplete: Boolean;
  private displayNoResults: Boolean;
  constructor(public navCtrl: NavController, public settings: SettingsProvider, public competitionProvider: CompetitionProvider) {
    this.competitions = [];
    this.apiError = false;
    this.loadComplete = false;
    // TODO Clean up
    this.competitionProvider.getCompetitions().then((val) => {
      val.subscribe((result) => {
        this.competitions = result;
        console.log(this.competitions);
        if(this.competitions.length <= 0) {
          this.displayNoResults = true;
        }
        this.loadComplete = true;
      });
    }).catch(err => {
      console.error(err);
      this.apiError = true;
    });

  }

  matches(){
    this.navCtrl.push(MatchesPage,{})
  }

}
