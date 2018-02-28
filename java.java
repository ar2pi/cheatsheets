import java.util.*;
import javax.swing.*;

public class Foo {

    private int[] array = new int[3];
    private List<Foo> array2 = new ArrayList<Foo>();
    private List<Foo> array3 = new LinkedList<Foo>();

    Foo() {
        this.array[0] = 1;
        this.array2.add(0, new Foo());
    }

    public static void main(String[] args) {
        Scanner inputs = new Scanner(System.in);
        int bar = inputs.nextInt();

        System.out.println("stuff");
        System.exit(0);

        JOptionPane.showInputDialog(null, "stuff");
        JOptionPane.showMessageDialog(null, "stuff");
    }

    public void doStuff3() {

    }

    private void doStuff() {

    }

    protected static final void doStuff2() {

    }

}