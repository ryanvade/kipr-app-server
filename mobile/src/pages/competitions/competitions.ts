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

  competitions:Array<{id:number,created_at:String,updated_at:String,name:String,location:String,start_date:String,end_date:String,ruleset_id:String}> = [];
  names:Array<String> = [];
  competitionId: number;

  constructor(public navCtrl: NavController, public navParams: NavParams, private competitionProvider: CompetitionProvider) {
    this.competitionProvider.getCompetitions().then(data=>{
      this.competitions = data;
    });
  }

  getCompetitions(event){
    var val = event.target.value;

    if(val && val.trim() != ''){
      this.competitions = this.competitions.filter((competition) => {
        return (competition.toString().toLowerCase().indexOf(val.toLowerCase()) > -1);
      })
    }

    this.ionViewDidLoad();
  }

  competitionClicked(competition){
    //this.competitionId = get "id" from competitions[];
    this.navCtrl.push(CompetitionInfoPage,
    {
        competitionID: competition.id
    });
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad CompetitionsPage');
  }

}
