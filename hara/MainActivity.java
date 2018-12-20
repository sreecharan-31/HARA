package com.example.sreecharan.hara;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class MainActivity extends AppCompatActivity {
    EditText name;
    Button register;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        name=(EditText)findViewById(R.id.editText2);
        register=(Button)findViewById(R.id.button2);

        register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                background bg=new background(getApplicationContext());
                String pass="nill";
                String type="login";
                bg.execute(type,name.getText().toString(),pass);
            }
        });
    }
}
