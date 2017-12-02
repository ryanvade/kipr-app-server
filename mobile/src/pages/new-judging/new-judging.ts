import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { Slides } from 'ionic-angular';
import { ViewChild } from '@angular/core';
import { MatchesPage } from '../matches/matches'
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
  match: any;
  opponent: string;
  judgedOpponent: boolean;
  jsonRules = [];

  constructor(private remoteService:RemoteServiceProvider,public navCtrl: NavController, public navParams: NavParams) {
    this.rules=[];

    this.remoteService.getRules().subscribe(data=>{
      console.log(data);
      this.jsonRules = data;
      for(var _i = 0; _i < this.jsonRules.length; _i++){
          this.rules.push({
            value:this.jsonRules[_i].min,
            min:this.jsonRules[_i].min,
            max:this.jsonRules[_i].max,
            title:this.jsonRules[_i].title,
            description:this.jsonRules[_i].description,
            img:'assets/imgs/botguy.png'
          }
          );
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

  nextClicked(name,match){
    //need to push to self
    this.navCtrl.push(NewJudgingPage,
      {
        name: name,
        match: match,
        judgedOpponent: true
      }
    );
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

  matches(){
    this.navCtrl.push(MatchesPage,{})
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad NewJudgingPage');
  }

}
