import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { AdminComponent } from './admin.component';
import { AdminPannelComponent } from './admin-pannel/admin-pannel.component';
import { SubmitResultComponent } from './submit-result/submit-result.component';

@NgModule({
    declarations: [
        AdminComponent,
        AdminPannelComponent,
        SubmitResultComponent,
    ],
    imports: [
        CommonModule,
        SharedModule,
        FormsModule,
        RouterModule,
        ReactiveFormsModule,
    ],
})
export class AdminModule {}
