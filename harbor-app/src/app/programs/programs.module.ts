import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { FormsModule } from '@angular/forms';
import { SubmitService } from '../shared/services/submit.service';
import { AngularFirestoreModule } from 'angularfire2/firestore';
import { BuildSubmitComponent } from './build-submit.component';
@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    SharedModule,
    AngularFirestoreModule.enablePersistence(),
  ],
  declarations: [
    BuildSubmitComponent,
  ],
  providers: [SubmitService]
})
export class ProgramsModule {}
