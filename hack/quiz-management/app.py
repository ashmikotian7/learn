from flask import Flask, render_template

app = Flask(__name__)

@app.route('/')
def home():
    return render_template('home.html')

# Faculty Routes
@app.route('/faculty-login')
def faculty_login():
    return render_template('login_faculty.html')

@app.route('/faculty-signup')
def faculty_signup():
    return render_template('fsignup.html')

@app.route('/faculty-dashboard')
def faculty_dashboard():
    return render_template('ff.html')

# Admin Routes
@app.route('/admin-login')
def admin_login():
    return render_template('login_admin.html')

@app.route('/admin-signup')
def admin_signup():
    return render_template('asignup.html')

@app.route('/admin-dashboard')
def admin_dashboard():
    return render_template('af.html')

# Student Routes
@app.route('/student-login')
def student_login():
    return render_template('login_student.html')

# Contact Us Page
@app.route('/contact')
def contact():
    return render_template('contact.html')  # Make sure 'contact.html' exists in the 'templates' folder

if __name__ == '__main__':
    app.run(debug=True)
