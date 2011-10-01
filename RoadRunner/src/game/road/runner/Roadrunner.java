package game.road.runner;

import java.util.TimerTask;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnCancelListener;
import android.graphics.drawable.AnimationDrawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Handler;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

public class Roadrunner extends Activity {
	public int clickCount = 0;
	public Handler uiHandler;
	public Context context = this;
	public TextView mTextField;
	public TextView mTextClickCount;
	public TextView mTextStatus;
	public boolean isStart = false;
	public long preNanoSeconds, postNanoSeconds, rsltTimeStamp;

	/** Called when the activity is first created. */
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.main);
		mTextClickCount = (TextView) findViewById(R.id.txtClickCountShow);
		mTextField= (TextView) findViewById(R.id.txtClickCount);
		Button btnLeft = (Button) findViewById(R.id.btnLeft);
		Button btnRight = (Button) findViewById(R.id.btnRight);
		new LoadMoreEntries().execute(null, null, null);

		OnClickListener pedals = new OnClickListener() {

			@Override
			public void onClick(View v) {
				if (isStart) {
					clickCount++;
					mTextClickCount.setText("Click Count: "
							+ String.valueOf(clickCount));

					postNanoSeconds = System.nanoTime();
					rsltTimeStamp = (postNanoSeconds - preNanoSeconds) / 1000000000;
					if (rsltTimeStamp > 9 && clickCount > 30) {
						startAnimationFast();
						//mTextStatus.setText("FAST");
					} else if (rsltTimeStamp > 7 && clickCount > 20) {
						startAnimationMedium();
						//mTextStatus.setText("MEDIUM");
					} else if (rsltTimeStamp > 4 && clickCount > 10) {
						startAnimationSlow();
						//mTextStatus.setText("SLOW");
					}
				}
			}
		};

		btnRight.setOnClickListener(pedals);
		btnLeft.setOnClickListener(pedals);

//		final ImageButton v = (ImageButton) findViewById(R.id.btnLeft);
//        v.setOnTouchListener(new OnTouchListener() {
//            @Override
//            public boolean onTouch(View arg0, MotionEvent arg1) {
//                switch (arg1.getAction()) {
//                case MotionEvent.ACTION_DOWN: {
//                    v.setImageResource(R.drawable.left_pressed);
//                    break;
//                }
//                case MotionEvent.ACTION_CANCEL:{
//                    v.setImageResource(R.drawable.left_pedal);
//                    break;
//                }
//                }
//                return true;
//            }
//        });
//		btnLeft.setOnClickListener(new OnClickListener() {
//
//			@Override
//			public void onClick(View v) {
//				if (isStart) {
//					clickCount++;
//					mTextClickCount.setText("Click Count: "
//							+ String.valueOf(clickCount));
//
//					postNanoSeconds = System.nanoTime();
//					rsltTimeStamp = (postNanoSeconds - preNanoSeconds) / 1000000000;
//					if (rsltTimeStamp > 9 && clickCount > 30) {
//						startAnimationFast();
//						mTextStatus.setText("FAST");
//					} else if (rsltTimeStamp > 7 && clickCount > 20) {
//						startAnimationMedium();
//						mTextStatus.setText("MEDIUM");
//					} else if (rsltTimeStamp > 4 && clickCount > 10) {
//						startAnimationSlow();
//						mTextStatus.setText("SLOW");
//					}
//				}
//			}
//		});

		// ImageView animation = (ImageView) findViewById( R.id.bg_Animation );
		// if (animation == null)
		// return;
		// animation.setBackgroundResource( R.drawable.bganimation );
		//
		// AnimationRoutine animationRoutine = new AnimationRoutine( );
		//
		// Timer t = new Timer( false );
		// t.schedule( animationRoutine, 100 );

		// @Override
		//
		// public void onWindowFocusChanged (boolean hasFocus) {
		//
		// super.onWindowFocusChanged(hasFocus);
		// ImageView rocketImage1 = (ImageView) findViewById(R.id.imgBg);
		// AnimationDrawable frameAnimation =
		//
		// (AnimationDrawable) rocketImage1.getBackground();
		//
		// if(hasFocus) {
		//
		// frameAnimation.start();
		//
		// } else {
		//
		// frameAnimation.stop();
		//
		// }
		//
		// }
		//

	}

	public void startAnimationSlow() {
		ImageView rocketImage1 = (ImageView) findViewById(R.id.imgBg);
		rocketImage1.setBackgroundResource(R.drawable.bganimation_slow);
		AnimationDrawable frameAnimation = (AnimationDrawable) rocketImage1
				.getBackground();
		frameAnimation.start();
	}

	public void startAnimationMedium() {
		ImageView rocketImage1 = (ImageView) findViewById(R.id.imgBg);
		rocketImage1.setBackgroundResource(R.drawable.bganimation_medium);
		AnimationDrawable frameAnimation = (AnimationDrawable) rocketImage1
				.getBackground();
		frameAnimation.start();
	}

	public void startAnimationFast() {
		ImageView rocketImage1 = (ImageView) findViewById(R.id.imgBg);
		rocketImage1.setBackgroundResource(R.drawable.bganimation_fast);
		AnimationDrawable frameAnimation = (AnimationDrawable) rocketImage1
				.getBackground();
		frameAnimation.start();
	}

	public void startAnimation() {
		ImageView rocketImage1 = (ImageView) findViewById(R.id.imgBg);
		rocketImage1.setBackgroundResource(R.drawable.bganimation_start);
		AnimationDrawable frameAnimation = (AnimationDrawable) rocketImage1
				.getBackground();
		frameAnimation.start();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see android.app.Activity#onWindowFocusChanged(boolean)
	 */
//	@Override
//	public void onWindowFocusChanged(boolean hasFocus) {
//		// TODO Auto-generated method stub
//		super.onWindowFocusChanged(hasFocus);
//
//		startAnimation();
//		// ImageView rocketImage1 = (ImageView) findViewById(R.id.imgBg);
//		// AnimationDrawable frameAnimation =
//		//
//		// (AnimationDrawable) rocketImage1.getBackground();
//		//
//		// if(hasFocus) {
//		//
//		// frameAnimation.start();
//		//
//		// } else {
//		//
//		// frameAnimation.stop();
//		//
//		// }
//	}

	private class AnimationRoutine extends TimerTask {
		AnimationRoutine() {
		}

		public void run() {
			ImageView img = (ImageView) findViewById(R.id.bg_Animation);
			AnimationDrawable frameAnimation = (AnimationDrawable) img
					.getBackground();
			frameAnimation.start();
		}
	}
	
	public void showResultBox(){
		
			String eol = System.getProperty("line.separator");
			AlertDialog alertDialog;
			alertDialog = new AlertDialog.Builder(context).create();
			alertDialog.setTitle("Your Score!");
			alertDialog.setMessage("You  have clicked 200 times :) "+eol+" good luck"+eol+"yahoo!");
			alertDialog.show();
			alertDialog.setOnCancelListener(new OnCancelListener() {
				
				@Override
				public void onCancel(DialogInterface dialog) {
					// TODO Auto-generated method stub
					((Activity) context).finish();	
				}
			});
	}

	private class LoadMoreEntries extends AsyncTask<Void, Void, Void> {
		
		@Override
		protected void onPreExecute() {
			super.onPreExecute();

			clickCount = 0;
			isStart = true;
			preNanoSeconds = System.nanoTime();
			startAnimationSlow();

			new CountDownTimer(12000, 1000) {

				public void onTick(long millisUntilFinished) {
					mTextField.setText("Seconds: "							+ millisUntilFinished / 1000);
				}

				public void onFinish() {
					mTextField.setText("Seconds: 0");
					isStart = false;
					showResultBox();
//					mTextField.setText("done! You have clicked "
//							+ clickCount / 10 + " times per second!");
					
//					Toast.makeText(
//							context,
//							"You have clicked "
//									+ String.valueOf(clickCount)
//									+ " times ! :)", Toast.LENGTH_LONG);
				}
			}.start();
		}

		@Override
		protected Void doInBackground(Void... params) {
			return null;
		}

		@Override
		protected void onPostExecute(Void result) {
			super.onPostExecute(result);
		}
	}
	
	
}