import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { NewJudgingPage } from './new-judging';

@NgModule({
  declarations: [
    NewJudgingPage,
  ],
  imports: [
    IonicPageModule.forChild(NewJudgingPage),
  ],
})
export class NewJudgingPageModule {}
