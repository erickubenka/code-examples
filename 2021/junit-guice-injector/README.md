## Das Problem [code-fever.de Blog-Eintrag](https://code-fever.de/artikel/selenium-shadow-dom-und-shadow-root.html)

Schon seit Jahren arbeite ich in java-basierten Testautomatisierungsprojekten ausschließlich mit TestNG als
Testframework.  
Als ich im Jahr 2014 im Beruf die ersten Projekte umsetzte, konnte TestNG einfach durch ein besseres Feature-Set und
eine deutlich bessere parallelisierte Testausführung punkten.

Und auch in der heutigen Zeit, gefällt mir persönlich die Entwicklung von `TestNG` besser, zum Beispiel auch die
Guice-Integration. Mit einer simplen Annotation an einer Testklasse, kann das eigene `Guice`-Modul geladen und somit
Depenency-Injection deutlich vereinfacht werden.

Diesen Komfort gibt es in `JUnit` (5.7.2) nicht, jedoch lässt es sich mit einfachen Mitteln nachbauen und ich zeige wie.

## Entwicklungsumgebung einrichten

Ich arbeite mit Windows 10 und verwende `chocolatey` als Paketverwaltung, weil es das Reproduzieren von
Software-Installationen einfacher macht, weshalb ich kurz darstellen möchte, wie ich meine Entwicklungsumgebung
inklusive Java, JDV und Maven aufgesetzt habe.

````bash
choco install maven
choco install openjdk--version=16.0.1
````

## Dependency Injection Beispiel

Für die Veranschaulichung und Verwendung habe ich ein einfaches Beispiel gewählt. Es existiert ein
Interface `StorageInterface`, welches von einer oder mehreren Klassen implementiert werden kann. Zur Laufzeit benötige
ich in meinem Test einen Objekt-Storage, um beipsielsweise testübergreifende Daten abzulegen.  
Welche Implementation des `StorageInterface` dabei verwendet wird, interessiert mich in meinem Testfall selbst nicht.  
Im Vorfeld möchte ich mit einem Guice-Binding definieren, wie das Interface automatisch durch Guice aufgelöst werden
soll.

StorageInterface und Implementation

````java
public interface StorageInterface {

    String store(Object obj);

    Object get(String uuid);
}


public class HashMapStorage implements StorageInterface {

    private final HashMap<String, Object> storage = new HashMap<>();

    @Override
    public String store(Object obj) {
        final UUID uuid = UUID.randomUUID();
        storage.put(uuid.toString(), obj);
        return uuid.toString();
    }

    @Override
    public Object get(final String uuid) {
        return storage.get(uuid);
    }
}
````

## Guice Module erstellen

Nachdem nun klar ist, welche Klasse ich gern mittels `Guice` auflösen und injecten möchte, muss das Ganze in einem
`Guice`-Modul definiert werden.

````java
public final class JunitModule extends AbstractModule {

    protected void configure() {
        bind(StorageInterface.class).to(HashMapStorage.class).in(Scopes.SINGLETON);
    }
}
````

Als nächstes muss ein entsprechender `GuiceInjector` definiert werden. Dieser soll die entsprechenden Abhängigkeiten in
den Tests auflösen. Dazu muss im Kontext von JUnit das Interace `BeforeTestExecutionCallback` und dessen
Methode `beforeTestExecution` implementiert werden.  
Hier ist die Zeile `injector.injectMembers(o)` die entscheidene Handlung. Falls im `ExtensionContext` von
eine `TestInstance` vorhanden ist, so werden alle Guice-Member dieses Objekts automatisch aufgelöst.

````java
public class GuiceInjector implements BeforeTestExecutionCallback {

    protected Injector injector = Guice.createInjector(new JunitModule());

    @Override
    public void beforeTestExecution(ExtensionContext context) throws Exception {

        final Optional<Object> testInstance = context.getTestInstance();
        testInstance.ifPresent(o -> {
            injector.injectMembers(o);
            context.getStore(ExtensionContext.Namespace.create(getClass())).put(injector.getClass(), injector);
        });
    }
}
````

Um das Ganze in der Handhabung der `Guice`-Annotation von `TestNG` anzupassen, habe ich noch eine `WithGuice`-Annotation
erstellt, welche dann den Testklassen hinzugefügt werden muss.

````java

@ExtendWith(GuiceInjector.class)
@Retention(RetentionPolicy.RUNTIME)
@Target({ElementType.TYPE, ElementType.FIELD, ElementType.PARAMETER, ElementType.METHOD})
public @interface WithGuice {

    Class<? extends Module>[] modules() default {};
}
````

## Tests

Die Verwendung der Annotation und von Guice in den Testklassen selbst kann dann ganz nachdem Vorbild und Vorgehen von
TestNG passieren. Wichtig ist, entsprechende Member des Tests mit der `Inject`-Annotation und die Testklasse oder eine
abstrakte Testklasse mit der `WithGuice`-Annotation zu versehen.

````java

@WithGuice(modules = JunitModule.class)
public class StorageTest {

    @Inject
    StorageInterface storage;

    @Test
    public void testT01_SimplePassedTestCase() {

        Assertions.assertNotNull(storage, "Guice modules injected successfully");

        final String stringIdent = storage.store("Simple String");
        final String integerIdent = storage.store(10);
        Assertions.assertEquals(storage.get(stringIdent), "Simple String");
        Assertions.assertEquals(storage.get(integerIdent), 10);
    }
}
````

### Links

Internal:

- [GitHub Sources](https://github.com/erickubenka/code-examples/tree/master/2021/junit-guice-injector)

External:

- [Chocolatey](https://chocolatey.org/)
- [Maven](https://maven.apache.org/)
- [TestNG](https://testng.org/doc/)
- [JUnit](https://junit.org/junit5/)
- [Google Guice](https://github.com/google/guice)
