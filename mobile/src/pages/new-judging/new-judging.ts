import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { Slides } from 'ionic-angular';
import { ViewChild } from '@angular/core';

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

  rules: Array<{title:string, value:number, description:string}>;
  teamName: any;

  constructor(public navCtrl: NavController, public navParams: NavParams) {
    //this will be changed later to a for each loop to generate the rows
    this.rules = [
      {title:'Red ball(s) in bucket',value: 0,description:'Red ball(s) in bucket'},
      {title:'Blue ball(s) in bucket',value: 0,description:'Blue ball(s) in bucket'},
      {title:'Robots on Terrace',value: 0,description:'Robots on Terrace'},
      {title:'Super long',value: 0,description:'Super long description just to test something. Hopefully these are not that long.'},
      {title:'Robots on Terrace',value: 0,description:'Robots on Terrace'}
    ];

    this.teamName = navParams.get('name');
  }

  slideChanged() {
    let currentIndex = this.slides.getActiveIndex();
    console.log('Current index is', currentIndex);
  }

  nextClicked(){

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
    console.log('ionViewDidLoad NewJudgingPage');
  }

}
