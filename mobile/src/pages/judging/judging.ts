import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';

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

  rules: Array<{label:string, value:number}>;
  teamName: any;

  constructor(public navCtrl: NavController, public navParams: NavParams) {
    //this will be changed later to a for each loop to generate the rows
    this.rules = [
      {label:'Red ball(s) in bucket',value: 0},
      {label:'Blue ball(s) in bucket',value: 0},
      {label:'Robots on Terrace',value: 0},
      {label:'Super long description just to test something. Hopefully these are not that long.',value: 0},
      {label:'Robots on Terrace',value: 0}
    ];

    this.teamName = navParams.get('name');
  }

  increment(item){
    item.value = item.value + 1;
    console.log('add 1 to' + item);
  }

  decrement(item){
    if(item.value > 0){
        item.value = item.value - 1;
        console.log('subtract 1 to' + item);
    }
  }
  
  ionViewDidLoad() {
    console.log('ionViewDidLoad JudgingPage');
  }

}
