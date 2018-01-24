import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { Slides } from 'ionic-angular';
import { AlertController } from 'ionic-angular';
import { ViewChild } from '@angular/core';
import { MatchesPage } from '../matches/matches';
import { RemoteServiceProvider } from '../../providers/remote-service/remote-service';

/**
 * Generated class for the NewJudgingPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-new-judging',
  templateUrl: 'new-judging.html',
})

export class NewJudgingPage {

  @ViewChild(Slides) slides: Slides;

  rules: Array<{value:number,min:number, max:number, title:string, description:string, img:string}>;
  teamName: string;
  match: {title:string, teamA:string, teamB:string};
  opponent: string;
  judgedOpponent: boolean;
  jsonRules = [];

  constructor(private alertCtrl:AlertController,private remoteService:RemoteServiceProvider,public navCtrl: NavController, public navParams: NavParams) {
    this.rules=[];

    this.remoteService.getRules().subscribe(data=>{
      console.log(data);
      this.jsonRules = data;

      //Will try to make this look a little cleaner later on
      if(this.match.teamA === this.teamName){
        for(var _i = 0; _i < this.jsonRules.length; _i++){
            this.rules.push({
              value:this.jsonRules[_i].min,
              min:this.jsonRules[_i].min,
              max:this.jsonRules[_i].max,
              title:this.jsonRules[_i].title,
              description:this.jsonRules[_i].description,
              img:this.jsonRules[_i].imgA
            }
            );
        }
      }else{
        for(var _j = 0; _j < this.jsonRules.length; _j++){
            this.rules.push({
              value:this.jsonRules[_j].min,
              min:this.jsonRules[_j].min,
              max:this.jsonRules[_j].max,
              title:this.jsonRules[_j].title,
              description:this.jsonRules[_j].description,
              img:this.jsonRules[_j].imgB
            }
            );
        }
      }

    });

    this.teamName = navParams.get('name');
    this.match = navParams.get('match');
    this.judgedOpponent = navParams.get('judgedOpponent');

    if(this.match.teamA == this.teamName)
      this.opponent = this.match.teamB;
    else
      this.opponent = this.match.teamA;
  }

  slideChanged() {
    let currentIndex = this.slides.getActiveIndex();
    console.log('Current index is', currentIndex);
  }

  increment(item){
    if(item.value < item.max){
      item.value = item.value + 1;
      console.log('add 1 to' + item);
    }
  }

  decrement(item){
    if(item.value > 0){
      item.value = item.value - 1;
      console.log('subtract 1 to' + item);
    }
  }

  dq(){
    let alert = this.alertCtrl.create({
      title: 'Confirm No Score',
      message: 'Are you sure this team is disqualified?',
      buttons:[
        {
          text:'No',
          role:'cancel',
          handler: () => {
            console.log('No Score Canceled');
          }
        },
        {
          text:'Yes',
          handler: () => {
            for(var _i; _i < this.rules.length; _i++){
                this.rules[_i].value = 0;
            }

            let length = this.slides.length();
            this.slides.slideTo(length);
            console.log('No Score Confirmed');
          }
        }
      ]
    });
    alert.present();
  }

  noScore(){
    let alert = this.alertCtrl.create({
      title: 'Confirm No Score',
      message: 'Are you sure this team scored no points?',
      buttons:[
        {
          text:'No',
          role:'cancel',
          handler: () => {
            console.log('No Score Canceled');
          }
        },
        {
          text:'Yes',
          handler: () => {
            for(var _i; _i < this.rules.length; _i++){
                this.rules[_i].value = 0;
            }

            let length = this.slides.length();
            this.slides.slideTo(length);
            console.log('No Score Confirmed');
          }
        }
      ]
    });
    alert.present();
  }

  nextClicked(name,match){
    //need to send score to JSON before judging next team
    //push to self
    this.navCtrl.push(NewJudgingPage,
      {
        name: name,
        match: match,
        judgedOpponent: true
      }
    );
  }

  matches(){
    //need to send score to JSON before leaving page
    this.navCtrl.popAll();
    this.navCtrl.push(MatchesPage,{})
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad NewJudgingPage');
  }

}
