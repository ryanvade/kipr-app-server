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
    this.listCompetitions();
  }

  async listCompetitions(){
    //get list from provider
    /*this.names = [
      'Region 1',
      'Region 2',
      'Region 3',
      'Red',
      'Blue',
      'Sky',
      'Hello'
    ];*/

    //shouldn't need names[]. Need to get "name" from JSONObject
    this.competitions = await this.competitionProvider.getCompetitions();
    this.names = [];
    for(var i = 0; i < this.competitions.length; i++){
      let comp = this.competitions[i];
      this.names.push((comp as any).name);
    }
    this.competitionProvider.getCompetitions().then(data=>{
      this.competitions = data;
    });
  }

  getCompetitions(event){
    var val = event.target.value;

    if(val && val.trim() != ''){
      this.names = this.names.filter((competition) => {
        return (competition.toLowerCase().indexOf(val.toLowerCase()) > -1);
      });
    }else {
      this.names = [];
      this.competitions.forEach((comp) => {
        this.names.push((comp as any).name);
      });
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
        competition: competition
    });
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad CompetitionsPage');
  }

}
