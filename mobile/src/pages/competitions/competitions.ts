import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';

/**
 * Generated class for the CompetitionsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-competitions',
  templateUrl: 'competitions.html',
})
export class CompetitionsPage {

  competitions: string[];

  constructor(public navCtrl: NavController, public navParams: NavParams) {
    this.listCompetitions();
  }

  listCompetitions(){
    //get list from provider
    this.competitions = [
      'Region 1',
      'Region 2',
      'Region 3',
      'Red',
      'Blue',
      'Sky',
      'Hello'
    ];
  }

  getCompetitions(event){
    var val = event.target.value;

    if(val && val.trim() != ''){
      this.competitions = this.competitions.filter((competition) => {
        return (competition.toLowerCase().indexOf(val.toLowerCase()) > -1);
      })
    }

    this.ionViewDidLoad();
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad CompetitionsPage');
  }

}
