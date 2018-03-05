import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { Slides } from 'ionic-angular';
import { AlertController } from 'ionic-angular';
import { ViewChild } from '@angular/core';
import { MatchesPage } from '../matches/matches';
import { RemoteServiceProvider } from '../../providers/remote-service/remote-service';
import { SocketProvider } from '../../providers/socket/socket';
import { Observable } from 'rxjs/Observable';

/**
 * Generated class for the JudgingPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-judging',
  templateUrl: 'judging.html',
})

export class JudgingPage {

  @ViewChild(Slides) slides: Slides;

  rules: Array<{value:number,min:number, max:number, title:string, description:string, img:string}>;
  teamName: string;
  match: {title:string, teamA:string, teamB:string};
  opponent: string;
  judgedOpponent: boolean;
  jsonRules = [];
  waiting: boolean = true;

  constructor(private alertCtrl:AlertController,private remoteService:RemoteServiceProvider,public navCtrl: NavController, public navParams: NavParams, public socket: SocketProvider) {
    this.rules=[];
    socket.connect();
    console.log(socket);
    this.match = {title: "", teamA: "", teamB: ""};

    this.getMatch().subscribe((channel) => {
      let data = event.data;
      data = JSON.parse(data.slice(data.indexOf(`"match"`) -1, data.length - 1));
      this.match.teamA = data.match.team_a.name;
      this.match.teamB = data.match.team_b.name;
      this.teamName = data.match.team_a.name;
      this.opponent = data.match.team_b.name;
      this.jsonRules = data.match.competition.ruleset;
      this.waiting = false;
    });
  }

  getMatch() {
    let observable = new Observable(observer => {
      this.socket.on('KIPR\\Events\\MatchSentToTable', (channel) => {
        observer.next(channel);
      });
    })
    return observable;
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
      title: 'Confirm DQ',
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
    this.navCtrl.push(JudgingPage,
      {
        name: name,
        match: match,
        judgedOpponent: true
      }
    );
  }

  matches(){
    //need to send score to JSON before leaving page

    this.navCtrl.setRoot(MatchesPage);
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad JudgingPage');
  }

}
