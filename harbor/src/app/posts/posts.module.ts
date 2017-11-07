import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { PostListComponent } from './post-list/post-list.component';
import { PostSingleComponent } from './post-single/post-single.component';
import { HttpClientModule } from '@angular/common/http';
@NgModule({
	imports: [
		CommonModule,
		SharedModule,
		HttpClientModule
	],
	declarations: [
		PostSingleComponent,
		PostListComponent
	],
})
export class PostsModule {}
