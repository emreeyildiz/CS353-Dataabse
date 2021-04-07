import java.sql.*;
import java.text.ParseException;
import java.text.SimpleDateFormat;

public class Connect {

    public static void refreshing(Connection connection) throws SQLException{
        Statement statement = connection.createStatement();
        statement.executeUpdate("DROP TABLE IF EXISTS apply");
        statement.executeUpdate("DROP TABLE IF EXISTS student");
        statement.executeUpdate("DROP TABLE IF EXISTS company");
        System.out.println("Refreshing is done");

    }

    public static void createTable(Connection connection) throws SQLException{

        String crate_student = "CREATE TABLE student(sid CHAR(12), sname VARCHAR(50), bdate DATE, address VARCHAR(50), scity VARCHAR(20), year CHAR(20), gpa FLOAT, nationality VARCHAR(20), PRIMARY KEY (sid)) ENGINE = InnoDB";
        String create_company = "CREATE TABLE company (cid CHAR (8), cname VARCHAR(20), quota INT, PRIMARY KEY(cid)) ENGINE = InnoDB";
        String create_apply = "CREATE TABLE apply(sid CHAR(12), cid CHAR(8), FOREIGN KEY (sid) REFERENCES student(sid), FOREIGN KEY (cid) REFERENCES company(cid), PRIMARY KEY(sid, cid)) ENGINE = InnoDB";
        Statement statement = connection.createStatement();
        statement.executeUpdate(crate_student);
        statement.executeUpdate(create_company);
        statement.executeUpdate(create_apply);
        System.out.println("Table creating is done");
    }

    public static void insertStudent(Connection connection, String sid, String sname, String bdate, String address, String scity, String year, float gpa, String nationality){
        try {
            PreparedStatement preparedStatement = connection.prepareStatement("INSERT INTO student VALUES(?,?, ?, ?, ?, ?, ?, ?)");
            preparedStatement.setString(1,sid);
            preparedStatement.setString(2,sname);
            java.util.Date date = new SimpleDateFormat("dd.MM.yyyy").parse(bdate);
            java.sql.Date dateSQL = new java.sql.Date(date.getTime());
            preparedStatement.setDate(3, dateSQL);
            preparedStatement.setString(4, address);
            preparedStatement.setString(5, scity);
            preparedStatement.setString(6, year);
            preparedStatement.setFloat(7, gpa);
            preparedStatement.setString(8, nationality);
            preparedStatement.execute();

        }catch (SQLException | ParseException exception){
            exception.printStackTrace();
        }
    }

    public static void insertApply(Connection connection, String sid, String cid){
        try {
            PreparedStatement preparedStatement = connection.prepareStatement("INSERT  INTO apply VALUES(?,?)");
            preparedStatement.setString(1, sid);
            preparedStatement.setString(2, cid);
            preparedStatement.execute();

        } catch (SQLException exception){
            exception.printStackTrace();
        }
    }

    public static void insertCompany(Connection connection, String cid, String cname, int quota){
        try {
            PreparedStatement preparedStatement = connection.prepareStatement("INSERT INTO company VALUES(?,?,?)");
            preparedStatement.setString(1, cid);
            preparedStatement.setString(2, cname);
            preparedStatement.setInt(3, quota);
            preparedStatement.execute();
        }catch (SQLException exception){
            exception.printStackTrace();
        }
    }


    public static void main(String[] args){
        String username = "emre.yildiz";
        String password = "cgVmBOl3";
        String jdbc = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr:3306/emre_yildiz";

        try {
            Connection connection = null;
            try {
                connection = DriverManager.getConnection(jdbc, username, password);
            } catch (SQLException exception){
                System.out.println("Connection Failed");
                exception.printStackTrace();
            }

            if(connection != null){
                refreshing(connection);
                createTable(connection);
                insertStudent(connection, "21000001", "John", "14.05.1999", "Windy", "Chicago", "senior", 2.33f, "US");
                insertStudent(connection, "21000002", "Ali", "30.09.2001", "Nisantasi", "Istanbul", "junior", 3.26f, "TC");
                insertStudent(connection, "21000003", "Veli", "25.02.2003", "Nisantasi", "Istanbul", "freshman", 2.41f, "TC");
                insertStudent(connection, "21000004", "Ayse", "15.01.2003", "Tunali", "Ankara", "freshman", 2.55f, "TC");

                insertCompany(connection, "C101", "microsoft", 2);
                insertCompany(connection, "C102", "merkez bankasi", 5);
                insertCompany(connection, "C103", "tai", 3);
                insertCompany(connection, "C104", "tubitak", 5);
                insertCompany(connection, "C105", "aselsan", 3);
                insertCompany(connection, "C106", "havelsan", 4);
                insertCompany(connection, "C107", "milsoft", 2);

                insertApply(connection, "21000001", "C101");
                insertApply(connection, "21000001", "C102");
                insertApply(connection, "21000001", "C103");
                insertApply(connection, "21000002", "C101");
                insertApply(connection, "21000002", "C105");
                insertApply(connection, "21000003", "C104");
                insertApply(connection, "21000003", "C105");
                insertApply(connection, "21000004", "C107");

                System.out.println("Table filled");

                Statement statement = connection.createStatement();
                ResultSet resultSet = statement.executeQuery("SELECT * FROM student");
                while (resultSet.next()){
                    System.out.println();
                    System.out.println("ID: " + resultSet.getString("sid"));
                    System.out.println("Name: " + resultSet.getString("sname"));
                    System.out.println("Birthdate: " + resultSet.getDate("bdate"));
                    System.out.println("Adress: " + resultSet.getString("address"));
                    System.out.println("City: " + resultSet.getString("scity"));
                    System.out.println("Year: " + resultSet.getString("year"));
                    System.out.println("GPA: " + resultSet.getFloat("gpa"));
                    System.out.println("Nationality: " + resultSet.getString("nationality"));
                    System.out.println();

                }
                connection.close();
            }
        }
        catch (SQLException exception){
            System.out.println(exception.getErrorCode()  + exception.getMessage());
            exception.printStackTrace();
        }

    }
}
