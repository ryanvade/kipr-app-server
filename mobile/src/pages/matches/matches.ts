import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { NewJudgingPage } from '../new-judging/new-judging';
//import { JudgingPage } from '../judging/judging'
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
    //will be generated based off of the how many teams sign up
    this.matches = [
      {title:'Match 1',teamA:'Team A', teamB:'Team B'},
      {title:'Match 2',teamA:'Team C', teamB:'Team D'},
      {title:'Match 3',teamA:'Team E', teamB:'Team F'}
    ];
  }

  teamTapped(name){
    this.navCtrl.push(NewJudgingPage,
      {
        name: name
      }
    );
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad MatchesPage');
  }

}
