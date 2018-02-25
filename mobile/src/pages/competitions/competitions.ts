import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { CompetitionInfoPage } from '../competitionInfo/competitionInfo';
import { CompetitionProvider } from '../../providers/competition/competition';
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

  competitions: Object[];
  competitionNames: String[];
  competitionId: number;

  constructor(public navCtrl: NavController, public navParams: NavParams, private competitionProvider: CompetitionProvider) {
    this.listCompetitions();
  }

  async listCompetitions(){
    //get list from provider
    /*this.competitionNames = [
      'Region 1',
      'Region 2',
      'Region 3',
      'Red',
      'Blue',
      'Sky',
      'Hello'
    ];*/

    //shouldn't need competitionNames[]. Need to get "name" from JSONObject
    this.competitions = await this.competitionProvider.getCompetitions();
    for(var i = 0; i < this.competitions.length; i++){
      this.competitionNames[i] = this.competitions.toString();
    }
  }

  getCompetitions(event){
    var val = event.target.value;

    if(val && val.trim() != ''){
      this.competitionNames = this.competitionNames.filter((competition) => {
        return (competition.toLowerCase().indexOf(val.toLowerCase()) > -1);
      })
    }

    this.ionViewDidLoad();
  }

  competitionClicked(competition){
    //this.competitionId = get "id" from competitions[];
    this.navCtrl.push(CompetitionInfoPage,
    {
        competitionID: this.competitionId
    });
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad CompetitionsPage');
  }

}
