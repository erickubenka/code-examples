/*
 * Copyright (c) 2020 Eric Kubenka.
 * Licensed under the MIT License.
 * See LICENSE.md file in the project root for full license information.
 */
package de.codefever.junit.inject;

import com.google.inject.Guice;
import com.google.inject.Injector;
import org.junit.jupiter.api.extension.BeforeTestExecutionCallback;
import org.junit.jupiter.api.extension.ExtensionContext;

import java.util.Optional;

/**
 * @author Eric Kubenka
 * creation date: 09.07.2021 - 08:20
 */
public class GuiceInjector implements BeforeTestExecutionCallback {

    protected Injector injector = Guice.createInjector(new JunitModule());

    @Override
    public void beforeTestExecution(ExtensionContext context) throws Exception {

        final Optional<Object> testInstance = context.getTestInstance();
        testInstance.ifPresent(o -> {
            injector.injectMembers(o);
            getStore(context).put(injector.getClass(), injector);
        });
    }

    private ExtensionContext.Store getStore(ExtensionContext context) {
        return context.getStore(ExtensionContext.Namespace.create(getClass()));
    }
}