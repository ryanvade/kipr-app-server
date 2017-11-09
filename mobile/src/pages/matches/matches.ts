import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { JudgingPage } from '../judging/judging'
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

  matches: Array<{title:string, teamA:string, teamB:string}>;
  constructor(public navCtrl: NavController, public navParams: NavParams) {
    this.matches = [
      {title:'Match 1',teamA:'Team A', teamB:'Team B'},
      {title:'Match 2',teamA:'Team C', teamB:'Team D'},
      {title:'Match 3',teamA:'Team E', teamB:'Team F'}
    ];
  }

  buttonTapped(name){
    this.navCtrl.push(JudgingPage,
      {
        name: name
      }
    );
  }
/*
  matchTapped(match){
    this.navCtrl.push(JudgingPage,
    {
        match:match
    })
  }*/

  ionViewDidLoad() {
    console.log('ionViewDidLoad MatchesPage');
  }

}
