import java.util.Scanner; 

public class TugasKonversiTipeData {
    public static void main(String[] args) {
        Scanner input = new Scanner(System.in);

        System.out.print("Masukkan sebuah teks: ");
        String data = input.nextLine();

       
        try {
            int angka = Integer.parseInt(data);
            System.out.println("Ke Integer: " + angka + " | Tipe: int");
        } catch (NumberFormatException e) {
            System.out.println("Tidak bisa dikonversi ke Integer");
        }

       
        try {
            double angkaDouble = Double.parseDouble(data);
            System.out.println("Ke Double: " + angkaDouble + " | Tipe: double");
        } catch (NumberFormatException e) {
            System.out.println("Tidak bisa dikonversi ke Double");
        }

        
        if (data.length() == 1) {
            char karakter = data.charAt(0);
            System.out.println("Ke Char: " + karakter + " | Tipe: char");
        } else {
            System.out.println("Tidak bisa dikonversi ke Char (harus 1 karakter).");
        }

        input.close(); 
    }
}